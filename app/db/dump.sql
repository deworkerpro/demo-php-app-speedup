DROP TABLE IF EXISTS blog_posts;

CREATE TABLE blog_posts
(
    id               UUID         NOT NULL,
    date             TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
    slug             VARCHAR(255) NOT NULL,
    content_title    VARCHAR(255) NOT NULL,
    content_short    TEXT         DEFAULT NULL,
    content_text     TEXT         DEFAULT NULL,
    meta_title       VARCHAR(255) DEFAULT NULL,
    meta_description TEXT         DEFAULT NULL,
    PRIMARY KEY (id)
);

CREATE UNIQUE INDEX UNIQ_78B2F932989D9B62 ON blog_posts (slug);

CREATE INDEX IDX_78B2F932AA9E377A ON blog_posts (date);

INSERT INTO blog_posts VALUES (
    '8821ad9a-1648-4e3e-9db6-5b4eb0425a38',
    '2023-02-12 16:45:12',
    'lorem-ipsum',
    'Published Post',
    '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>',
    '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>',
    'Post title',
    'Post description'
);
