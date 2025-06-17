<h3>Редактировать запись #<?= htmlspecialchars($editFaq['id']) ?></h3>
<form action="/admin/edit/faq_update.php" method="POST">
  <input type="hidden" name="id" value="<?= htmlspecialchars($editFaq['id']) ?>">

  <label>Раздел:
    <input type="text" name="section_title" value="<?= htmlspecialchars($editFaq['section_title']) ?>" required>
  </label>

  <label>Вопрос:
    <textarea name="question" rows="2" required><?= htmlspecialchars($editFaq['question']) ?></textarea>
  </label>

  <label>Ответ:
    <textarea name="answer" rows="4" required><?= htmlspecialchars($editFaq['answer']) ?></textarea>
  </label>

  <button type="submit">Сохранить</button>
  <a href="?section=faq" class="btn-secondary">Отмена</a>
</form>