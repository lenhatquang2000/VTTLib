import re

def check_tags(file_path):
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Simple regex to find HTML tags
    # Ignore blade directives, comments, and PHP blocks
    content_clean = re.sub(r'{{.*?}}', '', content)
    content_clean = re.sub(r'{!!.*?!!}', '', content_clean)
    content_clean = re.sub(r'<\?php.*?\?>', '', content_clean, flags=re.DOTALL)
    content_clean = re.sub(r'<!--.*?-->', '', content_clean, flags=re.DOTALL)
    
    # Match tags like <div> or </div>
    tag_regex = re.compile(r'<(/?[a-zA-Z0-9\-]+)(?:\s+[^>]*?)?>')
    
    stack = []
    
    # Split content by lines to track line numbers
    lines = content.split('\n')
    
    for i, line in enumerate(lines):
        line_num = i + 1
        # Find all tags in this line
        for match in tag_regex.finditer(line):
            tag = match.group(1)
            is_closing = tag.startswith('/')
            tag_name = tag.lstrip('/')
            
            # Skip self-closing tags
            if tag_name.lower() in ['img', 'br', 'hr', 'input', 'link', 'meta']:
                continue
            # Skip tags that end with /
            if match.group(0).endswith('/>'):
                continue
                
            if is_closing:
                if not stack:
                    print(f"Error: Unexpected closing tag </{tag_name}> at line {line_num}")
                else:
                    last_tag, last_line = stack.pop()
                    if last_tag != tag_name:
                        print(f"Error: Mismatched closing tag </{tag_name}> at line {line_num} (expected </{last_tag}> from line {last_line})")
            else:
                stack.append((tag_name, line_num))
                
    if stack:
        print("Error: Unclosed tags remaining:")
        for tag, line in stack:
            print(f"- <{tag}> opened at line {line}")

check_tags('resources/views/site/pages/home.blade.php')
