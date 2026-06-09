# Digimaya CRM — Components Module Notes

> **Pair with**: `notes-general.md`
> **Scope**: Blade components, layouts, design system, Tailwind pipeline
> **Audience access**: super_admin, admin (UI/component management)

---

## 1. PHILOSOPHY

Components module = **reusable UI building blocks**. NOT a feature for end-users. It's the **design system** + Blade component library that powers all other modules' UI.

**Principle**: Build once, reuse everywhere. When pattern repeats 3+ times, extract to component.

---

## 2. ROLE STAKEHOLDERS

| Role | Components Access |
|---|---|
| `super_admin` | Full (manage components, design system) |
| `admin` | View only (no edit unless dev) |
| Other roles | NO ACCESS |

This is a developer/super-admin module. Most agency users won't touch this.

---

## 3. SUB-MODULES OVERVIEW

| Sub-module | Purpose |
|---|---|
| Blade Components | Reusable UI elements (`x-input-label`, `x-modal`, etc.) |
| Layouts | Page structure templates (admin, app, public) |
| Design Tokens | Colors, spacing, typography variables in CSS |
| Tailwind Config | Build pipeline + custom utilities |
| Comparison Rows | Marketing widget (comparison tables — used in some content) |

---

## 4. BLADE COMPONENTS LOCATION

```
resources/views/components/
├── application-logo.blade.php
├── breadcrumb.blade.php
├── danger-button.blade.php
├── dropdown.blade.php
├── dropdown-link.blade.php
├── input-error.blade.php
├── input-label.blade.php
├── modal.blade.php                 (used heavily for Edit/Review modals across modules)
├── nav-link.blade.php
├── primary-button.blade.php
├── responsive-nav-link.blade.php
├── secondary-button.blade.php
└── text-input.blade.php
```

---

## 5. KEY COMPONENTS

### `<x-input-label>`
Form field label with consistent styling.
```blade
<x-input-label for="field_name" value="Label Text" />
```
Renders: `<label for="field_name" class="block font-medium text-sm text-gray-700">Label Text</label>`

### `<x-text-input>`
Standard text input with consistent styling.
```blade
<x-text-input id="field_name" name="field_name" type="text" 
              class="mt-1 block w-full" 
              :value="old('field_name', $model->field_name)" 
              required />
```

### `<x-input-error>`
Validation error display (auto-hidden if no error).
```blade
<x-input-error :messages="$errors->get('field_name')" class="mt-2" />
```

### `<x-primary-button>`
Main submit button.
```blade
<x-primary-button>Submit</x-primary-button>
```
Standard styling: `bg-gray-800 text-white px-4 py-2 rounded-md uppercase text-xs font-semibold tracking-widest hover:bg-gray-700`.

### `<x-secondary-button>`
Cancel/secondary actions. Lighter color than primary.

### `<x-danger-button>`
Destructive actions (delete confirmations).

### `<x-modal>`
Modal dialog with Alpine integration.
```blade
<x-modal name="modal-name" maxWidth="2xl" focusable>
    <div class="p-6">
        ... modal content ...
    </div>
</x-modal>
```

**Open/close via dispatch**:
```blade
<button @click="$dispatch('open-modal', 'modal-name')">Open</button>
<button @click="$dispatch('close-modal', 'modal-name')">Close</button>
```

**Pattern used in**: Edit Followup, Complete Followup, Edit Project Report, AM Review modals.

### `<x-breadcrumb>`
Navigation breadcrumb. Pass items array.
```blade
<x-breadcrumb :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Projects', 'url' => route('admin.projects.index')],
    ['label' => $project->name]
]" />
```

Last item: no URL = current page (text only). Earlier items: clickable links.

### `<x-dropdown>` + `<x-dropdown-link>`
Dropdown menu (used in nav, action buttons).

### `<x-nav-link>` + `<x-responsive-nav-link>`
Top nav links (desktop and mobile responsive variants).

### `<x-application-logo>`
Digimaya logo SVG component. Used in nav header.

---

## 6. LAYOUTS

### `layouts/app.blade.php`
**Purpose**: Breeze-style auth layout. Used for login, register, password reset pages.

**Structure**:
```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>...</title>
    <link href="..." rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div class="min-h-screen flex flex-col items-center pt-6 sm:justify-center sm:pt-0 bg-gray-100">
        <div>
            <a href="/"><x-application-logo /></a>
        </div>
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
    @stack('scripts')
</body>
</html>
```

### `layouts/admin.blade.php`
**Purpose**: Admin panel layout (used by all `<x-app-layout>` admin pages).

**Structure**:
- Top nav (with dropdowns for Marketing/CRM/Operations/Finance/Components/System)
- Page header section (`{{ $header ?? '' }}` slot)
- Main content area (`{{ $slot }}`)
- Includes: `@stack('styles')` in head, `@stack('scripts')` before body close
- Includes duplicate-prevention JS (auto-disable submit buttons + spinner)

### `layouts/public.blade.php`
**Purpose**: Public-facing pages (homepage, blog, contact, etc.)

**Structure**:
- Public header (logo, nav menu: Home, About, Blog, Contact)
- Main content slot
- Footer with social links, contact info, copyright
- SEO meta tags (title, description, OG tags) via `@yield` or component

---

## 7. DESIGN SYSTEM

### CSS Source File
**Location**: `resources/css/app.css`  
**Compiled to**: `public/css/tailwind.css` (minified)  
**Build command**: `tw-build` alias

### Color Tokens
**Brand color**: `#165DFF` (Digimaya blue, preserved in design migration)

**Standard palette** (Tailwind defaults used):
- Indigo: primary action color (`indigo-600`, `indigo-100` etc)
- Gray: neutral (text, borders, backgrounds)
- Green: success states
- Yellow: warning, in-progress
- Red: error, critical
- Blue: info
- Cyan: special role highlight (Advertiser badge)

### Status Color Mapping
**Project status**:
- `active` → green
- `paused` → yellow
- `completed` → gray

**Project Report health**:
- `healthy` → green
- `needs_attention` → yellow
- `critical` → red

**Project Report status (workflow)**:
- `open` → blue
- `in_progress` → yellow
- `resolved` → green

**Lead status**:
- `new` → blue
- `contacted` → yellow
- `screened` → indigo
- `promoted` → green
- `disqualified` → gray

**Client status**:
- `prospect` → blue
- `active` → green
- `inactive` → gray
- `churned` → red

**Invoice status**:
- `unpaid` → yellow (or red if overdue)
- `paid` → green

**User role badge colors**:
- `super_admin` → purple
- `admin` → blue
- `marketing` → amber
- `account_manager` → indigo
- `advertiser` → cyan

### Typography
- Base font: System default (Tailwind default sans-serif stack)
- Code/Mono: Tailwind default mono
- Sizes: `text-xs`, `text-sm`, `text-base`, `text-lg`, `text-xl`, `text-2xl`
- Weights: `font-normal`, `font-medium`, `font-semibold`, `font-bold`

### Spacing
- Standard scale: 0, 1, 2, 3, 4, 5, 6, 8, 10, 12, 16
- Pagination wrapper margin: `mt-12` (consistent across all index pages)
- Card padding: `p-4` (compact) or `p-6` (standard)

---

## 8. TAILWIND PIPELINE

### Config File
**Location**: `tailwind.config.js`

**Content paths** (what files to scan for class usage):
```js
content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
],
```

### Build Workflow
1. Edit blade file with new class
2. Run `tw-build` (alias for `npx tailwindcss -i resources/css/app.css -o public/css/tailwind.css --minify`)
3. Hard refresh browser (`Cmd+Shift+R`) to bypass CSS cache
4. Verify class applied

**JIT Mode**: Active. Arbitrary values like `left-[-9999px]`, `min-w-[200px]`, `px-3.5` ARE compiled correctly.

### Common Issue: Class Not Applied
If a Tailwind class doesn't render after `tw-build`:
1. Check `tailwind.config.js` `content` array — does it include the file path?
2. Check for typo in class name
3. Check if CSS cache is stale → hard refresh
4. Check if Tailwind safelist excluded it (rare)

---

## 9. CSS VARIABLES (Design Tokens)

Custom variables defined in `resources/css/app.css` `:root` block:
```css
:root {
    --color-brand: #165DFF;
    /* Other custom tokens if defined */
}
```

Usage in blade:
```blade
<div style="color: var(--color-brand);">...</div>
```

Or as Tailwind arbitrary value:
```blade
<div class="text-[#165DFF]">...</div>
```

**Note**: Most styling uses Tailwind utility classes directly (no need for custom CSS in most cases).

---

## 10. ICON SYSTEM

### Inline SVG (Default)
Most icons inline as SVG in blade templates. Examples:
- Chevron arrows (dropdown indicator)
- Edit pencil, Delete trash icons
- Status icons

### Lucide Icons (if added)
Optional: Lucide React icons via CDN if needed for complex iconography.

### Heroicons (Tailwind-friendly)
Available pattern: copy SVG from heroicons.com inline as needed.

---

## 11. COMPARISON ROWS (Marketing Widget)

Some marketing pages use comparison tables. Module:

### `comparison_rows` (data table)
```
id, title, label, before_value, after_value, display_order, timestamps
```

### `Admin\ComparisonRowController`
- Resource CRUD
- Used for "Before vs After" type marketing widgets on landing pages

### View
Renders as comparison table on public pages (homepage feature section, etc.).

---

## 12. ADMIN UI PATTERNS LIBRARY

### Page Header Structure
```blade
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $title }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="..." />
                </div>
            </div>
            <div class="flex items-center gap-2">
                <!-- Action buttons (Edit, Delete, etc.) -->
            </div>
        </div>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Content -->
        </div>
    </div>
</x-app-layout>
```

### Card Wrapper
```blade
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <!-- Content inside card -->
    </div>
</div>
```

### Section Heading inside Card
```blade
<h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Section Title</h3>
```

### Definition List (read-only details)
```blade
<dl class="grid grid-cols-1 gap-4 mb-8">
    <div>
        <dt class="text-xs uppercase text-gray-500">Label</dt>
        <dd class="mt-1 text-sm text-gray-900">{{ $value }}</dd>
    </div>
</dl>
```

### Status Badge
```blade
<span class="inline-flex px-2 py-1 text-xs rounded-full {{ $colorClass }}">
    {{ $label }}
</span>
```

Where `$colorClass` is one of:
- `bg-green-100 text-green-800`
- `bg-yellow-100 text-yellow-800`
- `bg-red-100 text-red-800`
- `bg-blue-100 text-blue-800`
- `bg-gray-100 text-gray-800`
- `bg-indigo-100 text-indigo-800`
- `bg-cyan-100 text-cyan-800`
- `bg-purple-100 text-purple-800`
- `bg-amber-100 text-amber-800`

### Success/Error Flash
```blade
@if (session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
@endif
```

### Table Standard
```blade
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Column</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($items as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm text-gray-900">{{ $item->field }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-12">{{ $items->links() }}</div>
```

---

## 13. DUPLICATE PREVENTION SCRIPT

Bundled in `layouts/admin.blade.php`. JS auto-disables submit buttons and shows spinner on form submit.

**Behavior**:
- On any form submit, the submit button gets:
  - `disabled` attribute
  - Spinner SVG injected (or text changed)
- Prevents accidental double-clicks
- Re-enabled on validation error or page reload

**Opt-out**: Add `data-no-disable` attribute to form or button.
```blade
<form data-no-disable>...</form>
```

**Backend pair**: `PreventDuplicateAdminSubmits` middleware (5s dedup window via cache, sha256 of user+route+payload).

---

## 14. ALPINE.JS PATTERNS

### Toggle Show/Hide
```blade
<div x-data="{ open: false }">
    <button @click="open = !open">Toggle</button>
    <div x-show="open" x-cloak>Content</div>
</div>
```

`x-cloak`: hide element until Alpine initializes (prevents flash of content). Add to CSS:
```css
[x-cloak] { display: none !important; }
```

### Repeatable Form Items
```blade
<div x-data="{ items: @json($items) }">
    <template x-for="(item, idx) in items" :key="idx">
        <div>
            <input x-model="item.field">
            <button @click="items.splice(idx, 1)">Remove</button>
        </div>
    </template>
    <button @click="items.push({field: ''})">Add</button>
</div>
```

### Dispatch Event Pattern
Trigger child component update from parent:
```blade
<!-- Trigger -->
<button @click="$dispatch('load-data', { id: 5, name: 'foo' })">Click</button>

<!-- Listener -->
<div x-data="{ id: null, name: '' }"
     x-on:load-data.window="id = $event.detail.id; name = $event.detail.name;">
    <span x-text="id"></span>
    <span x-text="name"></span>
</div>
```

### Modal with Pre-fill (Edit Pattern)
Combination of `$dispatch('open-modal')` + custom event:
```blade
<button @click="
    $dispatch('open-modal', 'edit-item');
    $dispatch('load-edit-item', { id: {{ $item->id }}, ...fields... });
">Edit</button>

<x-modal name="edit-item">
    <div x-data="{ id: null, ...fields... }"
         x-on:load-edit-item.window="id = $event.detail.id; ...">
        <form :action="`/admin/items/${id}`">
            ...
        </form>
    </div>
</x-modal>
```

---

## 15. KNOWN GOTCHAS

### x-cloak Required for Initial Hidden State
If element should be hidden on page load, MUST add `x-cloak`:
```blade
<div x-show="open" x-cloak>...</div>
```

Without `x-cloak`: element flashes visible briefly before Alpine initializes. With `x-cloak`: element hidden via CSS until Alpine ready.

### Tailwind Class Not Applied After Edit
1. Did you run `tw-build`?
2. Did you hard refresh browser?
3. Check `tailwind.config.js` `content` paths include the file
4. Check the class name (typo, capitalization)

### Modal Inside Form Bug
Don't nest `<form>` inside `<x-modal>` if modal is inside another `<form>`. HTML doesn't support nested forms.

Pattern: Modal lives at page level (bottom of layout), NOT inside item card form.

### Component Props Casing
Blade components: kebab-case attribute → camelCase prop.
```blade
<x-modal max-width="2xl">  <!-- attribute -->
```
```php
// inside modal.blade.php
@props(['maxWidth' => 'md'])  <!-- prop -->
```

### Slot vs Named Slot
- Default content: `{{ $slot }}`
- Named slot: `<x-slot name="header">...</x-slot>` then `{{ $header }}` inside component

### Dropdown z-index Issues
If dropdown gets cut off by another element, increase z-index:
```blade
<div class="absolute z-50 ...">  <!-- z-50 typically enough -->
```

### Modal Backdrop Click Close
Default `<x-modal>` closes on backdrop click. To prevent (mis. for forms in progress):
```blade
<x-modal name="..." :closeable="false">
```
(if component supports this prop)

### Alpine + Form Double Submit Conflict
If form has `@submit.prevent` Alpine handler AND submits via traditional POST, may double-fire. Use one or the other consistently.

### Mobile Nav Responsive Behavior
Top nav has 2 versions:
- Desktop: `<x-nav-link>` in horizontal layout
- Mobile: `<x-responsive-nav-link>` in collapsible hamburger menu

When adding new top nav item, MUST update BOTH desktop dropdown AND mobile menu section in `layouts/admin.blade.php` (or `layouts/navigation.blade.php` if extracted).

### Tailwind Arbitrary Values vs Standard Scale
Prefer standard scale (`px-4`, `mt-12`) over arbitrary (`px-[16px]`, `mt-[48px]`):
- Standard: shorter, more consistent
- Arbitrary: only when needed (mis. exact alignment to design)

### Component Override
Cannot override Blade component without editing file directly. To customize, either:
1. Pass props to existing component
2. Create new component (mis. `<x-custom-modal>`)
3. Extend existing via inheritance pattern (rare)
