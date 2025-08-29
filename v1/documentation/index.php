<?php
set_time_limit(60 * 10);

echo '
<style>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap");

body {
  font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
  font-size: 16px;
  line-height: 1.7;
  color: #c9d1d9;
  background-color: #0d1117;
  margin: 0;
  padding: 1rem 1.25rem;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

main, article, .content, .container, .wrap, .markdown-body {
  max-width: 960px;
  margin-left: auto;
  margin-right: auto;
}

h1, h2, h3, h4 {
  font-weight: 600;
  color: #f0f6fc;
  margin-top: 1.75rem;
  margin-bottom: 0.75rem;
  line-height: 1.35;
}

h1 {
  font-size: 2.1rem;
  border-bottom: 1px solid #30363d;
  padding-bottom: 0.4rem;
}

h2 {
  font-size: 1.6rem;
  border-bottom: 1px solid #30363d;
  padding-bottom: 0.3rem;
}

h3 {
  font-size: 1.3rem;
  color: #c9d1d9;
}

h4 {
  font-size: 1.05rem;
  color: #8b949e;
}

p {
  margin: 0.75rem 0;
  color: #c9d1d9;
}

blockquote {
  margin: 1rem 0;
  padding: 0.75rem 1rem;
  border-left: 0.3em solid #6e7681;
  color: #8b949e;
  background-color: #161b22;
  border-radius: 4px;
}

a {
  color: #58a6ff;
  font-weight: 500;
  text-decoration: none;
  transition: color 0.15s ease, text-decoration-color 0.15s ease;
}

a:hover {
  color: #79c0ff;
  text-decoration: underline;
  text-underline-offset: 2px;
}

hr {
  border: 0;
  border-top: 1px solid #30363d;
  margin: 1.5rem 0;
}

ul, ol {
  padding-left: 2rem;
  margin: 0.75rem 0;
}

li {
  margin: 0.35rem 0;
}

table {
  border-collapse: collapse;
  margin: 1rem 0;
  width: 100%;
  color: #c9d1d9;
}

th, td {
  border: 1px solid #30363d;
  padding: 0.5rem 1rem;
}

th {
  background-color: #161b22;
  font-weight: 600;
}

td {
  background-color: #0d1117;
}

pre, code {
  font-family: "Fira Code", monospace;
  font-size: 0.95rem;
  background-color: #161b22;
  color: #c9d1d9;
  border-radius: 6px;
}

pre {
  padding: 1rem;
  overflow-x: auto;
  margin: 1rem 0;
}

code {
  padding: 0.25rem 0.5rem;
}

p code, li code {
  background-color: #0d1117;
  color: #c9d1d9;
  border-radius: 4px;
}

@media (min-width: 1200px) {
  main, article, .content, .container, .wrap, .markdown-body {
    max-width: 1040px;
  }
}

p.collapsible {
  max-height: 2em;
  overflow: hidden;
  position: relative;
  padding-right: 1.5em;
  cursor: pointer;
  transition: max-height 0.3s ease;
}

p.collapsible::after {
  content: "▶";
  position: absolute;
  right: 0;
  top: 0;
  transform: rotate(0deg);
  transition: transform 0.3s ease;
}

p.collapsible.expanded {
  max-height: 100vh;
}

p.collapsible.expanded::after {
  transform: rotate(90deg);
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
  document.querySelectorAll("p").forEach(p => {
    const lineHeight = parseFloat(getComputedStyle(p).lineHeight);
    const lines = Math.round(p.scrollHeight / lineHeight);
    
    if (lines > 1) {
      p.classList.add("collapsible");
      p.addEventListener("click", () => p.classList.toggle("expanded"));
    }
  });
});
</script>
';

require '/var/www/.structure/library/base/vendor.php';
require '/var/www/.structure/library/base/utilities.php';

use League\CommonMark\CommonMarkConverter;

$markdown = @timed_file_get_contents(
    "https://raw.githubusercontent.com/IdealisticAI/Documentation/refs/heads/main/BigManage.md",
    3
);

if ($markdown === false) {
    echo "Error: Unable to retrieve the documentation.";
} else {
    require '/var/www/.structure/library/base/form.php';
    require_once '/var/www/.structure/library/ai/init.php';
    require_once '/var/www/.structure/library/account/init.php';
    $language = strtolower(trim(get_form_get("language")));
    $english = "english";

    switch ($language) {
        case "greek":
        case $english:
            break;
        default:
            $language = $english;
            break;
    }
    $account = new Account();
    $translation = $account->getTranslation()->translate(
        $english,
        $language,
        $markdown
    );

    if ($translation instanceof MethodReply
        && $translation->isPositiveOutcome()) {
        try {
            $converter = new CommonMarkConverter();
            echo $converter->convert($translation->getObject());
        } catch (Throwable $e) {
            echo "Error: Unable to calculate the documentation.";
        }
    } else {
        echo "Error: Unable to translate the documentation.";
    }
}