<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerJ6WFUWx\srcApp_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerJ6WFUWx/srcApp_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerJ6WFUWx.legacy');

    return;
}

if (!\class_exists(srcApp_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerJ6WFUWx\srcApp_KernelDevDebugContainer::class, srcApp_KernelDevDebugContainer::class, false);
}

return new \ContainerJ6WFUWx\srcApp_KernelDevDebugContainer([
    'container.build_hash' => 'J6WFUWx',
    'container.build_id' => '6f0c526e',
    'container.build_time' => 1556710176,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerJ6WFUWx');