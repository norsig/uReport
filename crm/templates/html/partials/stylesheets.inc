<link rel="stylesheet" type="text/css" href="<?= BASE_URI; ?>/css/screen.css" />
<link rel="stylesheet" type="text/css" href="<?= BASE_URI; ?>/css/print.css" media="print">
<link rel="stylesheet" type="text/css" href="<?= JQUERY_UI; ?>/jquery-ui.css" />
<?php
    if ($this->theme && is_file($this->theme.'/public/css/screen.css')) {
        $uri = BASE_URI.'/'.THEME.'/css/screen.css';
        echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$uri\" />";
    }
?>