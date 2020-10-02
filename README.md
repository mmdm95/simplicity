# Simplicity Framework
A simple yet nice PHP framework

## Attention

Please run following commands before use:

```
composer install
```

```
npm install
```

**NOTE:** If you don't have `manifest.json` inside `public/build` 
directory, then create it because it is needed for framework to work.

**NOTE:** Also you need to have `.env` file to prevent error. Duplicate 
`.env.example` and fill variables with your needs.

**NOTE:** If you pass previous notes, it'll be error again because 
you have to config your `webpack.common.js`, `webpack.dev.js` and 
`webpack.prod.js` too! So be careful...

then run

```
// for development
npm run sim-dev

// for production
npm run sim-build
```

or in **hard way** run

```
php sim webpack:clean-files
```

then

```
// for development
npm run start

// for production
npm run build
```

and then run

```
php sim webpack:move-files
```

# License
Under MIT license.