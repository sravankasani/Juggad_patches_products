<?php

namespace Drupal\juggad_products_module\Plugin\Block;

use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\Core\Block\BlockBase;
use CodeItNow\BarcodeBundle\Utils\QrCode;
use Drupal\Core\Render\Markup;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "juggad_barcode_block",
 *   admin_label = @Translation("Jugaad Product QR Code Block"),
 *   category = @Translation("QR Code block"),
 * )
 */
class JuggadBarCodeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Getting the current node id.
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof NodeInterface && $node->bundle() == 'juggad_patches') {
      $nid = $node->id();
      $node = Node::load($nid);
      $product_title = $node->title->value;
      $link = $node->get('field_purchase_link')->getValue()[0]['uri'];
      $link = preg_replace('/internal:/i', '', $node->field_purchase_link->uri);
      try {
        $qrCode = new QrCode();
        $qrCode->setText($link)
          ->setSize(300)
          ->setPadding(10)
          ->setErrorCorrection('high')
          ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0])
          ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0])
          ->setLabel('Scan Qr Code')
          ->setLabelFontSize(16)
          ->setImageType(QrCode::IMAGE_TYPE_PNG);

        $qrCode_image = Markup::create('<img src="data:' . $qrCode->getContentType() . ';base64,' . $qrCode->generate() . '" />');
      }
      catch (Exception $e) {
        // Log the exception to watchdog.
        \Drupal::logger('type')->error($e->getMessage());
      }
      return [
        '#markup' => $qrCode_image,
      ];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
