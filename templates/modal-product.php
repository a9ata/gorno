<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once  __DIR__ . '/../includes/functions.php'; 
?>
<div class="product-modal hidden" id="productModal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeProductModal()">&times;</span>
    <div id="productDetails">

    </div>
  </div>
</div>