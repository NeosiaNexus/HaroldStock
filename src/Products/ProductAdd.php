<?php

namespace Products;

use Entity\ProductType;

class ProductAdd
{

    public static function addFrom(string $action): string
    {
        $html = <<<HTML
                            <form action="$action" method="post">
                                <input type="text" name="add_product_name" placeholder="Nom du produit" required data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="1000">
                                <input type="number" name="add_product_quantity" placeholder="Quantité" required data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="1100">
                                <select name="add_product_selector" required data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="1200">
                                    <option disabled selected value>Type de produit</option>

HTML;
        foreach (ProductType::findAll() as $product) {
            $html .= <<<HTML
                                    <option value="{$product->getId()}">{$product->getName()}</option>

HTML;

        }

        $html .= <<<HTML
                                </select>
                                    <input type="text" name="add_product_description" placeholder="Description" data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="1300">
                                    <input type="number" name="add_product_critical" placeholder="Quantité critique" data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="1400">
                                    <input type="submit" name="add_product_submit" value="Ajouter" data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="1500">

HTML;


        $html .= <<<HTML
                            </form>

HTML;
        return $html;

    }
}