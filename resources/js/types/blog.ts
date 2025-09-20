import type { BaseEntity } from './base';
import type { Author } from './user';

export interface Post extends BaseEntity {
  title: string;
  content: string;
  excerpt: string;
  slug: string;
  author: Author;
  comments_count: number;
  comments?: Comment[];
}

export interface Comment extends BaseEntity {
  comment: string;
  author: Author;
  post: {
    id: number;
    title: string;
  };
}
