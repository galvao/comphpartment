![ComPHPartment's Logo](https://raw.githubusercontent.com/galvao/comphpartment/master/media/compartmentLogo_300x45.png)

# ComPHPartment
## Pocket's API access through PHP and Guzzle

ComPHPartment is a component designed to provide access to [Pocket](https://getpocket.com)'s [API](https://getpocket.com/developer/docs/overview) through [PHP](https://php.net/) and [Guzzle](https://github.com/guzzle/guzzle).

### Present and Future Features

- [x] Create Content;
- [x] Retrieve Content list;
- [ ] Retrieve a single item;
- [ ] Update Content;
- [ ] Delete Content;
- [ ] Synchronize Content;
- [ ] Create Tags;
- [ ] Retrieve list of current Tags;
- [ ] Update Tags;
- [ ] Delete Tags;
- [ ] Add Tags;
- [ ] Remove Tags;

For more information see the [issues](https://github.com/galvao/comphpartment/issues) page.

### Installation

Installation is done via [Composer](https://getcomposer.org):

```bash
$ composer require galvao/comphpartment:0.1.0-alpha
```
#### Requirements:

ComPHPartment uses [Guzzle](https://github.com/guzzle/guzzle) for performing the API access and [Monolog](https://github.com/Seldaek/monolog) for logging the entire process.

### Usage

1. Get a Consumer Key at Pocket;
2. Paste that key at config.json.dist and save it as config.json;
3. See a full working example at the public-example folder.

### Contributing / ToDo

#### Contributors

All issues, being enhancements, bugs, etc... must be filled through the issues page.

#### Contributing Guidelines

1. All code must be compliant to PSRs #1, #2 and #4
