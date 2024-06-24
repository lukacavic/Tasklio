import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Project/Clusters/KnowledgeBase/**/*.php',
        './resources/views/filament/project/clusters/knowledge-base/**/*.blade.php',
        './vendor/jaocero/activity-timeline/resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/awcodes/filament-table-repeater/resources/**/*.blade.php',
    ],
}
