<?php

namespace App\Services;

use App\DB\DB;
use App\Entity\Category;
use App\Entity\Product;
use App\Services\NotificationService as Notify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProductService extends AbstractController
{
    /**
     * @var DB
     */
    private $db;

    /**
     * @var Notify
     */
    private $notify;

    public function __construct(DB $db, Notify $notify)
    {
        $this->db = $db;
        $this->notify = $notify;
    }

    public function getAll()
    {
        return @$this->db->read()['products'];
    }

    public function save()
    {
        $data = @$this->db->read();

        $products = $data['products'];

        $this->id = $this->id ?: @key(@array_slice($products, -1, 1, true)) + 2;
        $product = $this->convertToArray();

        $key = array_search($this->id, array_column($products, 'id'));

        !is_bool($key) && isset($products[$key]) ? $products[$key] = $product : $products[] = $product;

        if (!empty($products)) {
            $data['products'] = $products;
            $this->db->write($data);
        }
    }

    public function getProductById($id)
    {
        $product = null;
        if ($data = @$this->db->read()['products']) {
            $search = array_search($id, array_column($data, 'id'));
            $product = !is_bool($search) ? @$data[$search] : null;

            if ($product && !empty($product)) {
                $this->id = $product['id'];
                $this->name = $product['name'];
                $this->category_id = $product['category_id'];
                $this->price = $product['price'];
                $this->qt = $product['qt'];
            }
        }

        return $product;
    }

    public function delete()
    {
        $data = @$this->db->read();

        $products = $data['products'];
        $key = array_search($this->id, array_column($products, 'id'));

        if (!is_bool($key) && isset($products[$key])) {
            unset($products[$key]);
        }

        if (!empty($products)) {
            $data['products'] = $products;
            $this->db->write($data);
        }
    }

    public function convertToArray()
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'category_id' => $this->category_id,
            'price'       => $this->price,
            'qt'          => $this->qt,
        ];
    }

    public function createFromArray($data)
    {
        return new self(
            $data['id'],
            $data['name'],
            $data['category_id'],
            $data['price'],
            $data['qt']
        );
    }

    public function fromJsonToDB()
    {
        $manager = $this->getDoctrine()->getManager();
        $products = $this->getAll();
        foreach ($products as $product) {
            $productEntity = new Product();

            $productEntity->setId($product['id']);
            $productEntity->setName($product['name']);
            $productEntity->setCategoryId($product['category_id']);
            $productEntity->setPrice($product['price']);
            $productEntity->setQt($product['qt']);

            $manager->persist($productEntity);
        }
        $manager->flush();
    }

    /**
     * @param Request $request
     *
     * @return Product
     */
    public function createProduct(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $productEntity = new Product();

        $requestProduct = $request->request->get('product', []);

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->find($requestProduct['category']);

        $productEntity->setName($requestProduct['name']);
        $productEntity->setCategory($category);
        $productEntity->setPrice($requestProduct['price']);
        $productEntity->setQt($requestProduct['qt']);

        $productEntity->setCreatedAt(new \DateTime('now'));
        $productEntity->setUpdatedAt(new \DateTime('now'));

        $goOnSale = new \DateTime($requestProduct['goOnSale']);
        $tomorrow = new \DateTime('tomorrow');
        if ($goOnSale < $tomorrow) {
            $goOnSale = $tomorrow;
        }

        $productEntity->setGoOnSale($goOnSale);

        $manager->persist($productEntity);
        $manager->flush();

        $this->notify($productEntity, 'created');

        return $productEntity;
    }

    /**
     * @param $product
     *
     * @return mixed
     */
    public function updateProduct($product)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($product);
        $entityManager->flush();

        $this->notify($product, 'changed');

        return $product;
    }

    /**
     * @param Product $product
     * @param string  $action
     */
    public function notify(Product $product, string $action): void
    {
        $title = 'Product '.$action;
        $html = $this->renderView(
            'product/show.html.twig',
            ['products' => [$product]]);

        $this->notify->notify($title, $html, ['email']);
    }
}
