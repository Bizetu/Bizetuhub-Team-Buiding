@twillBlockTitle('Theme and backgrounds')
@twillBlockIcon('colors')
@twillBlockGroup('app')

<x-twill::select
    name="theme_mode"
    label="Site color theme"
    :options="[
        [ 'value' => 'dark', 'label' => 'Dark theme' ],
        [ 'value' => 'light', 'label' => 'Light theme' ],
    ]"
    default="dark"
    note="Controls whether site cards, text, forms and backgrounds use the dark glass styling or light surfaces."
/>

<x-twill::input name="tag_line" label="Site tagline" />
<x-twill::medias name="logo" label="Company logo" :max="1" />
<x-twill::medias name="background" label="Global site background image" :max="1" />
<x-twill::input name="background_overlay" label="Background overlay strength (0-90%)" type="number" min="0" max="90" note="Adjusts the background image overlay for the selected light or dark theme." />

<x-twill::color name="background_color" label="Base background color" />
<x-twill::color name="text_color" label="Body text color" />
<x-twill::color name="heading_color" label="Heading color" />
<x-twill::color name="primary_color" label="Primary theme color" />
<x-twill::color name="secondary_color" label="Secondary theme color" />
<x-twill::color name="third_color" label="Accent color" />
<x-twill::color name="menu_color" label="Navigation text color" />
<x-twill::color name="menu_color_active" label="Navigation hover and active color" />
<x-twill::color name="header_background_color" label="Header background color" />
<x-twill::color name="footer_color" label="Footer background color" />
<x-twill::color name="enroll_color" label="Main action button color" />
<x-twill::color name="progress_color" label="Progress color" />
<x-twill::color name="retake_color" label="Retake color" />
<x-twill::color name="completed_color" label="Completed color" />
