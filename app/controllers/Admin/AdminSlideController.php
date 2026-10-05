<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Validator;
use App\Models\HeroSlide;
use App\Models\Product;

class AdminSlideController
{
    public function index(): void
    {
        $slides = HeroSlide::all();
        View::render('admin/slides/index', [
            'slides' => $slides,
            'pageTitle' => 'Manage Hero Slider',
        ], 'layouts/admin');
    }

    public function create(): void
    {
        $products = Product::allActive();
        View::render('admin/slides/form', [
            'slide' => null,
            'products' => $products,
            'pageTitle' => 'Add Hero Slide',
            'errors' => [],
        ], 'layouts/admin');
    }

    public function store(): void
    {
        $input = $_POST;
        $validator = new Validator();
        $isValid = $validator->validate($input, [
            'eyebrow' => 'required|max:100',
            'title' => 'required|max:150',
            'subline' => 'required|max:255',
            'image' => 'required|max:255',
        ]);

        if (!$isValid) {
            $products = Product::allActive();
            View::render('admin/slides/form', [
                'slide' => $input,
                'products' => $products,
                'pageTitle' => 'Add Hero Slide',
                'errors' => $validator->errors(),
            ], 'layouts/admin');
            return;
        }

        HeroSlide::create($input);
        flash('success', 'Hero slide added successfully.');
        redirect('/admin/slides');
    }

    public function edit(string $id): void
    {
        $slide = HeroSlide::findById((int)$id);
        if (!$slide) {
            flash('error', 'Slide not found.');
            redirect('/admin/slides');
        }

        $products = Product::allActive();
        View::render('admin/slides/form', [
            'slide' => $slide,
            'products' => $products,
            'pageTitle' => 'Edit Hero Slide',
            'errors' => [],
        ], 'layouts/admin');
    }

    public function update(string $id): void
    {
        $id = (int)$id;
        $input = $_POST;

        $validator = new Validator();
        $isValid = $validator->validate($input, [
            'eyebrow' => 'required|max:100',
            'title' => 'required|max:150',
            'subline' => 'required|max:255',
            'image' => 'required|max:255',
        ]);

        if (!$isValid) {
            $products = Product::allActive();
            $input['id'] = $id;
            View::render('admin/slides/form', [
                'slide' => $input,
                'products' => $products,
                'pageTitle' => 'Edit Hero Slide',
                'errors' => $validator->errors(),
            ], 'layouts/admin');
            return;
        }

        HeroSlide::update($id, $input);
        flash('success', 'Hero slide updated successfully.');
        redirect('/admin/slides');
    }

    public function delete(string $id): void
    {
        $id = (int)$id;
        HeroSlide::delete($id);
        flash('success', 'Hero slide removed.');
        redirect('/admin/slides');
    }
}
