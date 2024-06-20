import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Project/Clusters/KnowledgeBase/**/*.php',
        './resources/views/filament/project/clusters/knowledge-base/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
