# JavaScript Conventions

This directory contains project's JavaScript assets. Description of conventions
that must be kept inside it follows.

## Indexes

Indexes serve as entry points for JavaScript packer. They contain only module
imports from which the application build is made. Index file name is written in
kebab-case and always starts with `index`.

### file `index.js`

This file contains imports of critical JS and CSS modules, which are parts of
JS and CSS needed to render functional content above the fold.

### file `index-noncritical.js`

This file contains imports of noncritical JS and CSS modules.

## Utilities

Utilities are small reusable pieces of JavaScript code. They are located in
the `utilities/` directory and are imported where needed.

## Globals

Globals are functions that must be available in the global scope. In our case
this means on the `window` object. They are always assigned to the
`window.ngsite` object.

Generally, placing functionality in the global scope should be avoided, but
there are cases where other approaches are too awkward, and solving the problem
using the global scope is the most elegant one. Typical example is code that
needs to execute when input element changes, when the element can be added to
the DOM tree dynamically. The problem is solved by calling the function defined
in the global scope from the element's `onchange` attribute.

Calling the global function always has to be done explicitly through the
`window.ngsite` object, for example:

```html
<input type="file" onchange="window.ngsite.updateSelectedFileNames(event)">
```

Note: you should not use globals if other approaches are also possible, unless
the alternative is more awkward for implementation than it's justified.

### `globals/`

This directory contains specific global functions. The files inside it should be
named using kebab-case, for example:

```
update-selected-field-names.js
```

### `globals.js`

This file contains import and assignment of the global functions to the
`window.ngsite` object. It is imported from a index.

## Components

**Component** is a smaller piece of functionality, implemented in a single
JavaScript class. Typically, a component is using external libraries to apply
some functionality on the DOM tree, for example a swiper or a gallery. They can
also contain small applications whose implementation fits well in one file.

Components are applied on the page through the component configuration,
consisting of a component class, CSS selector and options object. This makes a
component reusable and enables application in multiple different contexts.

Component receives the element it applies on in its constructor and can store
it in a property, thus keeping its instance alive during the existence of the
element. This enables storing of the state inside the component itself.

Note: component should import its dependencies directly, without relying on the
global scope.

### directory `components/`

This directory contains specific components. Component file name must start
in pascal case, describing the component, and end in `.component.js`, for
example:

```
CookieControl.component.js
```

In case a component is used to mount an application, file name must end in
`.app.component.js`, for example:

```
ProductSearch.app.component.js
```

### file `components.js`

This file contains configuration and initialization of components. It is
imported from a index.

## Applications

Applications are bigger pieces of functionality usually implemented in multiple
files. They must be implemented in their own directory under the `apps/`
directory. Typical example of an application is a shop product search/filter
widget written in Vue or React.

While applications must be implemented as is described above, they must be
mounted on the page using a component. The file name of such a component must
follow a special convention which is described in the previous section.

## General

Console output is automatically stripped from the production build, hence you
are encouraged to use the console to provide meaningful information about
mistakes to the developer. This is particularly the case when implementing a
component.
