import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/App/**/*.php',
        './app/Filament/Project/**/*.php',
        './resources/views/filament/app/**/*.blade.php',
        './resources/views/filament/project/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/jaocero/activity-timeline/resources/views/**/*.blade.php',
    ],
}
