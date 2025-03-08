import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Responsavel/**/*.php',
        './resources/views/filament/responsavel/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
