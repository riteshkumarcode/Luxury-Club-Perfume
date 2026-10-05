<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Validator;
use App\Models\Product;
use App\Models\Category;

class AdminProductController
{
    public function index(): void
    {
        $products = Product::all();
        View::render('admin/products/index', [
            'products' => $products,
            'pageTitle' => 'Manage Fragrance Products',
        ], 'layouts/admin');
    }

    public function create(): void
    {
        $categories = Category::all();
        View::render('admin/products/form', [
            'product' => null,
            'categories' => $categories,
            'pageTitle' => 'Create New Fragrance',
            'errors' => [],
        ], 'layouts/admin');
    }

    public function store(): void
    {
        $input = $_POST;
        $validator = new Validator();
        $isValid = $validator->validate($input, [
            'category_id' => 'required|integer',
            'name' => 'required|min:2|max:150',
            'slug' => 'required|unique:products,slug',
            'size_label' => 'required|max:50',
            'price' => 'required|integer',
            'stock_qty' => 'required|integer',
        ]);

        $imageFile = $this->handleUpload('image_file');
        if (!$imageFile && empty($input['existing_image'])) {
            $validator->validate(['image' => ''], ['image' => 'required']);
            $isValid = false;
        }

        if (!$isValid) {
            $categories = Category::all();
            View::render('admin/products/form', [
                'product' => $input,
                'categories' => $categories,
                'pageTitle' => 'Create New Fragrance',
                'errors' => $validator->errors(),
            ], 'layouts/admin');
            return;
        }

        $image = $imageFile ?: ($input['existing_image'] ?? 'blue-orchid.png');

        Product::create(array_merge($input, ['image' => $image]));

        flash('success', "Product '{$input['name']}' created successfully.");
        redirect('/admin/products');
    }

    public function edit(string $id): void
    {
        $product = Product::findById((int)$id);
        if (!$product) {
            flash('error', 'Product not found.');
            redirect('/admin/products');
        }

        $categories = Category::all();
        View::render('admin/products/form', [
            'product' => $product,
            'categories' => $categories,
            'pageTitle' => "Edit: {$product['name']}",
            'errors' => [],
        ], 'layouts/admin');
    }

    public function update(string $id): void
    {
        $id = (int)$id;
        $input = $_POST;

        $validator = new Validator();
        $isValid = $validator->validate($input, [
            'category_id' => 'required|integer',
            'name' => 'required|min:2|max:150',
            'slug' => "required|unique:products,slug,$id",
            'size_label' => 'required|max:50',
            'price' => 'required|integer',
            'stock_qty' => 'required|integer',
        ]);

        if (!$isValid) {
            $categories = Category::all();
            $input['id'] = $id;
            View::render('admin/products/form', [
                'product' => $input,
                'categories' => $categories,
                'pageTitle' => "Edit: {$input['name']}",
                'errors' => $validator->errors(),
            ], 'layouts/admin');
            return;
        }

        $imageFile = $this->handleUpload('image_file');
        $image = $imageFile ?: ($input['existing_image'] ?? 'blue-orchid.png');

        Product::update($id, array_merge($input, ['image' => $image]));

        flash('success', "Product '{$input['name']}' updated successfully.");
        redirect('/admin/products');
    }

    public function delete(string $id): void
    {
        $id = (int)$id;
        Product::delete($id);
        flash('success', 'Product removed.');
        redirect('/admin/products');
    }

    private function handleUpload(string $field): ?string
    {
        if (empty($_FILES[$field]['tmp_name']) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $file = $_FILES[$field];
        if ($file['size'] > 3 * 1024 * 1024) {
            return null;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        $allowedMimes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if (!isset($allowedMimes[$mime])) {
            return null;
        }

        $ext = $allowedMimes[$mime];
        $filename = bin2hex(random_bytes(12)) . '.' . $ext;
        $dest = config('app.public_path') . '/assets/img/products/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return $filename;
        }

        return null;
    }
}
