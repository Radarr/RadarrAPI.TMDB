workflow "Close non template issues" {
  on = "issues"
  resolves = ["Filters for GitHub Actions"]
}

action "Filters for GitHub Actions" {
  uses = "helaili/github-graphql-action@f9197781e4fe192857ae3a20eb7b028b78097d38"
  args = "--query .github/graph_ql/query_issue.yaml --log true"
  secrets = ["GITHUB_TOKEN"]
}
