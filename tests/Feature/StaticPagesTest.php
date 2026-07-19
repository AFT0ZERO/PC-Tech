<?php

namespace Tests\Feature;

use Tests\TestCase;

class StaticPagesTest extends TestCase
{
    public function test_about_page_returns_200(): void
    {
        $this->get('/About')->assertStatus(200);
    }

    public function test_contact_us_page_returns_200(): void
    {
        $this->get('/Contact Us')->assertStatus(200);
    }

    public function test_faqs_page_returns_200(): void
    {
        $this->get('/FAQs')->assertStatus(200);
    }
}
