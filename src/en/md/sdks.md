# The Give Hub SDKs

The Give Hub provides official SDKs to help you integrate our crowdfunding platform into your applications. Our SDKs offer type-safe, idiomatic implementations for each supported language, complete with comprehensive documentation and examples. Each SDK provides full access to The Give Hub's features including campaign management, donations processing, impact tracking, and real-time notifications.

## Available SDKs

### JavaScript SDK
The JavaScript SDK provides seamless integration for both browser and Node.js environments, with support for modern ES6+ features and TypeScript definitions.

- **Documentation**: [/sdk/js](/sdk/js)
- **GitHub Repository**: [github.com/thegivehub/sdk-js](https://github.com/thegivehub/sdk-js)
- **Download**: [givehub-sdk-js-1.0.2.tgz](/sdk/givehub-sdk-js-1.0.2.tgz)
- **Package Manager**: `npm install givehub-sdk` or `yarn add givehub-sdk`

### PHP SDK
Our PHP SDK offers a clean, modern implementation supporting PHP 7.4+ with full composer integration and PSR compliance.

- **Documentation**: [/sdk/php](/sdk/php)
- **GitHub Repository**: [github.com/thegivehub/sdk-php](https://github.com/thegivehub/sdk-php)
- **Download**: [givehub-sdk-php-1.0.0.tgz](/sdk/givehub-sdk-php-1.0.2.tgz)
- **Package Manager**: `composer require givehub/sdk`

### Python SDK
The Python SDK provides async/await support and type hints, compatible with Python 3.8+ and modern async frameworks.

- **Documentation**: [/sdk/python](/sdk/python)
- **GitHub Repository**: [github.com/thegivehub/sdk-python](https://github.com/thegivehub/sdk-python)
- **Download**: [givehub-sdk-python-1.0.2.tgz](/sdk/givehub-sdk-python-1.0.2.tgz)
- **Package Manager**: `pip install givehub-sdk` or `poetry add givehub-sdk`

## Getting Started

Each SDK follows similar patterns for initialization and usage:

```javascript
// JavaScript
const givehub = new GiveHub({
    apiKey: 'your-api-key'
});
```

```php
// PHP
$givehub = new GiveHubSDK([
    'apiKey' => 'your-api-key'
]);
```

```python
# Python
givehub = GiveHubSDK({
    'api_key': 'your-api-key'
})
```

## SDK Features

- **Campaign Management**: Create and manage fundraising campaigns
- **Donation Processing**: Handle one-time and recurring donations
- **Impact Tracking**: Monitor and report on campaign impact metrics
- **Real-time Updates**: WebSocket support for live notifications
- **File Handling**: Upload and manage campaign media
- **Authentication**: Secure token-based authentication
- **Error Handling**: Comprehensive error handling and reporting

## Support

If you need help with our SDKs:

- **Documentation**: Visit the specific SDK documentation linked above
- **GitHub Issues**: Report issues on the respective GitHub repositories
- **Email Support**: Contact us at sdk-support@givehub.com
- **Community Forum**: Join our [developer community](https://community.givehub.com)

## Contributing

We welcome contributions to our SDKs! Please see the CONTRIBUTING.md file in each SDK's GitHub repository for guidelines.

## License

All our SDKs are released under the MIT License. See the LICENSE file in each repository for details.

## SDK Updates

Subscribe to our [developer newsletter](https://givehub.com/developers/newsletter) for updates about new SDK releases and features.
