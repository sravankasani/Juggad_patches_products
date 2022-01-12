<?php
namespace Drupal\barcode_block\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\file\Entity\File;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "my_block_example_block",
 *   admin_label = @Translation("My block"),
 *   category = @Translation("QR Code block"),
 * )
 */
class BarCodeBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {

    // Getting the current node id
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof \Drupal\node\NodeInterface) {
      // You can get nid and anything else you need from the node object.
      $nid = $node->id();
    }
    $host = \Drupal::service('request_stack')->getCurrentRequest()->getHost();
    ksm($host);
    // Function to get the product details
    $text = $this->getProductDetails($nid);
    // Function to generate the QR Code for app purchase link
    $path = $this->generateQrCodes($text['link']);
    $path = "http://juggad-patches.dd:8083/sites/default/files/Images/Qrcodes/Qrcode.png";
    // Render data to the QR Code block
    return [
      '#markup' => '<p>'.$text['title'].'</p><img src='.$path.' alt="picture" style="width:100px;height:75px;">',
    ];
  }

    /**
    * {@inheritdoc}
    */
    // Function to remove the caching
    public function getCacheMaxAge() {
      return 0;
    }

  public function generateQrCodes($qr_text) {
    // The below code will automatically create the path for the img.
    $path = '';
    $directory = "public://Images/QrCodes/";
    file_prepare_directory($directory, FILE_MODIFY_PERMISSIONS | FILE_CREATE_DIRECTORY);
    // Name of the generated image.
    $qrName = 'Qrcode';
    $uri = $directory . $qrName . '.png'; // Generates a png image.
    $path = drupal_realpath($uri);
    // Generate QR code image.
    \PHPQRCode\QRcode::png($qr_text, $uri, 'L', 4, 2);
    return $path;
  }
  public function getProductDetails($nid){
    $node = \Drupal\node\Entity\Node::load($nid); 
    $body = $node->body->value;
    $title = $node->title->value; 
    $link = preg_replace('/internal:/i', '', $node->field_purchase_link->uri);
    $data = [];
    $data['link'] = $link;
    $data['title'] =  $title; 
    return $data;
  }
}