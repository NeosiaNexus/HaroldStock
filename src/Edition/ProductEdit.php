<?php

namespace Edition;

use Entity\Exception\ProductNotFoundException;
use Entity\Product;
use Entity\ProductType;
use Service\Exception\SessionException;
use Service\Session;

class ProductEdit
{
    /**
     * @throws SessionException
     * @throws ProductNotFoundException
     */
    public static function editForm(int $productId, string $action): string
    {

        $product = Product::findById($productId);

        Session::start();
        $_SESSION['edit_product_id'] = $product->getId();

        $html = <<<HTML
        <form action="$action" method="post" class="edit__form">
            <input type="text" name="edit_name" value="{$product->getName()}" placeholder="Nom" required>
            <input type="number" name="edit_quantity" value="{$product->getQuantity()}" placeholder="Quantité" required>
            <select name="edit_product_selector" required data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="1200">
HTML;

        foreach (ProductType::findAll() as $getProduct) {

            if ($getProduct->getId() == $product->getProductType()) {
                $html .= <<<HTML
                <option value="{$getProduct->getId()}" selected>{$getProduct->getName()}</option>

HTML;
            } else {
                $html .= <<<HTML
                <option value="{$getProduct->getId()}">{$getProduct->getName()}</option>

HTML;
            }


        }

        $html .= <<<HTML
            </select>
            <input type="text" name="edit_description" value="{$product->getDescription()}" placeholder="Description">
            <input type="number" name="edit_critical" value="{$product->getCritical()}" placeholder="Quantité critique">
            <input type="submit" name="edit_submit" value="Enregistrer">
        </form>

HTML;

        return $html;
    }

}