import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Matricula/**/*.php',
        './resources/views/filament/matricula/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
