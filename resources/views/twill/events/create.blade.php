<x-twill::input
    name="title"
    label="Title"
    :required="true"
    on-change="formatPermalink"
/>

<x-twill::input
    name="slug"
    label="Key"
    :translated="true"
    ref="permalink"
    :prefix="$permalinkPrefix ?? ''"
/>
