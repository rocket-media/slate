# Markdown
set :markdown_engine, :redcarpet
set :markdown,
    fenced_code_blocks: true,
    smartypants: true,
    disable_indented_code_blocks: true,
    prettify: true,
    tables: true,
    with_toc_data: true,
    no_intra_emphasis: true

# Assets
set :css_dir, 'stylesheets'
set :js_dir, 'javascripts'
set :images_dir, 'images'
set :fonts_dir, 'fonts'

# Activate the syntax highlighter
activate :syntax

activate :autoprefixer do |config|
  config.browsers = ['last 2 version', 'Firefox ESR']
  config.cascade  = false
  config.inline   = true
end

# Github pages require relative links
activate :relative_assets
set :relative_links, true

# Build Configuration
configure :build do
  activate :minify_css
  activate :minify_javascript
  # activate :relative_assets
  # activate :asset_hash
  # activate :gzip
end

# Deploy
activate :deploy do |deploy|
  deploy.method = :rsync
  deploy.host          = 'dv5'
  deploy.path          = '/srv/users/rocketmedia/apps/rf-rocketmedia-com/docs'
  # Optional Settings
  # deploy.user  = 'garrett' # no default
  # deploy.port  = 22 # ssh port, default: 22
  # deploy.clean = true # remove orphaned files on remote host, default: false
  deploy.flags = '-rltDvzO --no-p --del' # add custom flags, default: -avz
  deploy.build_before = true
end
