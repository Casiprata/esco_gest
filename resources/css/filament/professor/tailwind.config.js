import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Professor/**/*.php',
        './resources/views/filament/professor/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
