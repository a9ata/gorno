<?php
require_once __DIR__ . '/../config/db.php';

// Получаем все FAQ
$result = $conn->query("SELECT * FROM faq ORDER BY section_title ASC");
$faq_items = $result->fetch_all(MYSQLI_ASSOC);

// Группируем по section_title
$grouped = [];
foreach ($faq_items as $item) {
  $grouped[$item['section_title']][] = $item;
}
?>

<section class="faq">
  <?php foreach ($grouped as $section => $items): ?>
    <div class="faq-category">
      <button class="category-toggle">
        <?= htmlspecialchars($section) ?>
        <img src="<?= ICONS_URL ?>formkit_down.svg" alt="">
      </button>
      <div class="category-content">
        <?php foreach ($items as $item): ?>
          <div class="faq-item">
            <div class="faq-question">
              <img class="icon" src="<?= ICONS_URL ?>question.svg"/>
              <?= htmlspecialchars($item['question']) ?>
            </div>
            <div class="faq-answer">
              <img class="icon" src="<?= ICONS_URL ?>answer.svg"/>
              <?= htmlspecialchars($item['answer']) ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>
</section>
