@twillBlockTitle('Homepage process')
@twillBlockIcon('text')
@twillBlockGroup('homepage')

<x-twill::input name="eyebrow" label="Eyebrow" :translated="true" />
<x-twill::input name="title" label="Section heading" :translated="true" />
@formField('block_editor', ['name' => 'process_steps', 'blocks' => ['home_process_step']])
