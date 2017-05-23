#PRIVATE CATEGORY

Private category plugin allows publishers to add private category or taxonomy to Posts or Custom Post types.
After activating plugin, a button will appear next to the selected category/taxonomy term metabox to mark selected category as private.
Private term id is stored in post_meta on save_post hook.
You can retrieve private category/taxonomy term id using get_primary_taxonomy_term($post_id, $taxonomy), both arguments are required.
