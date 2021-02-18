<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

mb_internal_encoding("UTF-8");
mb_http_output("UTF-8");

$p = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_STRING);
$p = FileHelper::clearPath($p);
$path = dirname(__FILE__) . DIRECTORY_SEPARATOR . $p;
$items = [];
$error = '';

try {
    $filesystem = new FilesystemIterator($path);
    // Отфильтровать папки.
    $dirs = new DirIterator($filesystem);
    // Отфильтровать картинки.
    $files = new FileIterator($filesystem, ['gif', 'jpg', 'png']);
    // Ссылка на родительскую папку.
    if ($p) {
        $parent = FileHelper::getParent($p);
        $items[] = ['name' => '..', 'href' => ($parent ? '/?p=' . $parent : '/')];
    }
    foreach ($dirs as $dir) {
        $items[] = [
            'name' => $dir->getFilename(),
            'href' => '/?p=' . ($p ? $p . DIRECTORY_SEPARATOR : '') . $dir->getFilename()
        ];
    }
    foreach ($files as $file) {
        $items[] = ['name' => $file->getFilename(), 'size' => FileHelper::getReadableSize($file->getSize())];
    }
} catch (UnexpectedValueException $e) {
    $error = $e->getMessage();
}

$loader = new Twig\Loader\FilesystemLoader(__DIR__ . '/../views');
$twig = new Twig\Environment($loader, [
    'autoescape' => 'html',
    'cache' => __DIR__ . '/../tmp/cache',
]);
$twig->display('index.twig', ['title' => 'Simple Image Browser', 'breadcrumbs' => $p, 'items' => $items,
    'error' => $error]);

