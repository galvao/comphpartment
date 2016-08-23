![ComPHPartment's Logo](https://github.com/galvao/comphpartment/media/comphpartmentLogo_300x45.png)

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

For more information see the issues page.

### Installation

Installation is done via [Composer](https://getcomposer.org):

```bash
$ composer require galvao/comphpartment:0.1.0-alpha
```
#### Requirements:

ComPHPartment uses Guzzle for performing the API access and Monolog for logging the entire process.

### Usage

1. Get a Consumer Key at Pocket;
2. Paste that key at config.json.dist and save it as config.json;
3. See a full working example at the public-example folder.

### Documentation

See [here]() for the PHPDocumentor's generated documentation.

### Contributing / ToDo

#### Contributors

See the Contributors page. Thanks for everyone who pitches in! =)
All issues, being enhancements, bugs, etc... must be filled through the issues page.

#### Contributing Guidelines

1. All code must be compliant to PSRs #1, #2 and #4

### Help needed at the following:

1. Tests: Automated testing is not between my strong suits, so I'd really appreciate if someone could give me some help at that area (Unit Testing, etc...).
2. Implementing a solid, PSR-6 compliant caching solution.
