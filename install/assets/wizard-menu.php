<nav class="wizard-menu">
  <ol>
    <?php foreach ($wizardSteps as $file => $label):
      // ahora usamos el índice tal cual (0-based)
      $num = array_search($file, $stepKeys, true);
      if      ($num <  $currentIndex) { $icon = '✅'; $cls = 'complete'; }
      elseif  ($num === $currentIndex) { $icon = '➡️'; $cls = 'current';  }
      else                             { $icon = '⚪'; $cls = '';         }
    ?>
      <li class="<?= $cls ?>">
        <span class="icon"><?= $icon ?></span>
        <?= $num ?>. <?= htmlspecialchars($label) ?>
      </li>
    <?php endforeach; ?>
  </ol>
</nav>
