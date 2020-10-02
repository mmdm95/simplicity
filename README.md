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