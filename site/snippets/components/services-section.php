<section class="services-section">
  <h2 class="services-section__title"><?= $page->services_title() ?></h2>
  <ul class="services-list">
    <?php foreach ($page->services_list()->toStructure() as $service): ?>
    <li class="services-list__item"><?= $service->name() ?></li>
    <?php endforeach ?>
  </ul>
</section>
