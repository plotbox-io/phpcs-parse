# phpcs-parse
Parse phpcs (Code Sniffer) output into standardised dto and allow re-output in several formats

**NOTE: Currently in early stage of development (Potentially subject to breaking changes)**

## Why ??
By parsing the result of phpcs commands, we can do advanced filtering or alter
the contents before re-outputting the results. The main use case is for implementing a
'shim' where you might want to filter out some style violations (e.g., if the lines
of code have not been touched on the current branch) 

## Example Usage

#### Convert from CSV to JSON

```
$converter = new Converter();
$csvIssuesString = file_get_contents(__DIR__ . '/phpcs-csv-example.txt');
$json = $converter->convert($csvIssuesString, 'csv', 'json');
```

### Supported Conversions

| Type | Read Input | Gen Output |
|------|------------|------------|
| csv  | YES        | NO         |
| xml  | NO         | YES        |
| json | NO         | YES        |

## Contributing

Please read [CONTRIBUTING.md] for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
