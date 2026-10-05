<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Validator;
use App\Models\Category;

class AdminCategoryController
{
    public function index(): void
    {
        $categories = Category::all();
        View::render('admin/categories/index', [
            'categories' => $categories,
            'pageTitle' => 'Manage Fragrance Categories',
        ], 'layouts/admin');
    }

    public function create(): void
    {
        View::render('admin/categories/form', [
            'category' => null,
            'pageTitle' => 'Create Category',
            'errors' => [],
        ], 'layouts/admin');
    }

    public function store(): void
    {
        $input = $_POST;
        $validator = new Validator();
        $isValid = $validator->validate($input, [
            'name' => 'required|min:2|max:100',
            'slug' => 'required|unique:categories,slug',
        ]);

        if (!$isValid) {
            View::render('admin/categories/form', [
                'category' => $input,
                'pageTitle' => 'Create Category',
                'errors' => $validator->errors(),
            ], 'layouts/admin');
            return;
        }

        Category::create($input);
        flash('success', "Category '{$input['name']}' created successfully.");
        redirect('/admin/categories');
    }

    public function edit(string $id): void
    {
        $category = Category::findById((int)$id);
        if (!$category) {
            flash('error', 'Category not found.');
            redirect('/admin/categories');
        }

        View::render('admin/categories/form', [
            'category' => $category,
            'pageTitle' => "Edit: {$category['name']}",
            'errors' => [],
        ], 'layouts/admin');
    }

    public function update(string $id): void
    {
        $id = (int)$id;
        $input = $_POST;

        $validator = new Validator();
        $isValid = $validator->validate($input, [
            'name' => 'required|min:2|max:100',
            'slug' => "required|unique:categories,slug,$id",
        ]);

        if (!$isValid) {
            $input['id'] = $id;
            View::render('admin/categories/form', [
                'category' => $input,
                'pageTitle' => "Edit: {$input['name']}",
                'errors' => $validator->errors(),
            ], 'layouts/admin');
            return;
        }

        Category::update($id, $input);
        flash('success', "Category '{$input['name']}' updated successfully.");
        redirect('/admin/categories');
    }

    public function delete(string $id): void
    {
        $id = (int)$id;
        Category::delete($id);
        flash('success', 'Category removed.');
        redirect('/admin/categories');
    }
}
