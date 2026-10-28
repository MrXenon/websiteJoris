<?php
/**
 * Counters: a row of animated counters.
 * Content = a bulleted list, one counter per item: "value | label".
 *   value: a number or calculation with {years:2019} (years since 2019) and {projects} (number of projects),
 *          for example "{projects} + 21" or "{projects} * 5".
 *   label: bold text is shown as the highlighted part, e.g. "<strong>Jaar</strong><br> werk ervaring".
 * Preview: icon-row
 */
$counterPlaceholders = ['projects' => $projects->getNrOfProjects('projects')];
$counters = [];
foreach (cms_elements(cms_content($fpc_obj->getFpcontent()), 'li') as $counterItem) {
    $counterParts = explode('|', $counterItem, 2);
    $counters[] = [
        'value' => cms_counter_value($counterParts[0], $counterPlaceholders),
        'label' => cms_strong_to_span(trim($counterParts[1] ?? '')),
    ];
}
$counterCount = count($counters);
?>
 <!-- start section -->
 <section<?= cms_anchor($fpc_obj) ?>>
            <div class="container">
                <div class="row row-cols-1 row-cols-md-<?= max(1, $counterCount) ?> row-cols-sm-2 align-items-center justify-content-center">
                    <?php foreach ($counters as $counterIndex => $counter):
                        // Bottom margins for stacked counters on small screens (same as the theme demo)
                        if ($counterIndex === $counterCount - 1) {
                            $counterMargin = '';
                        } elseif ($counterIndex === $counterCount - 2) {
                            $counterMargin = ' xs-margin-40px-bottom';
                        } else {
                            $counterMargin = ' sm-margin-40px-bottom';
                        }
                    ?>
                    <!-- start counter item -->
                    <div class="col text-center<?= $counterMargin ?> wow animate__fadeIn" data-wow-delay="<?= number_format(($counterIndex + 1) * 0.2, 1) ?>s">
                        <h3 class="vertical-counter d-inline-flex text-extra-dark-gray alt-font appear font-weight-600 letter-spacing-minus-2px mb-0" data-to="<?= (int)$counter['value'] ?>"></h3>
                        <span class="alt-font text-uppercase text-medium d-block margin-5px-top"><?= $counter['label'] ?></span>
                    </div>
                    <!-- end counter item -->
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <!-- end section -->
