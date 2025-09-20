import type { User } from './user';
import type { Post } from './blog';

export interface PageProps {
  auth: {
    user: User | null;
  };
  errors: Record<string, string>;
  translations: Record<string, any>;
  locale: string;
  fallbackLocale: string;
  availableLocales: Array<{
    code: string;
    name: string;
  }>;
}

export interface HomePageProps extends PageProps {
  posts: Post[];
}

export interface PostsIndexPageProps extends PageProps {
  posts: Post[];
}

export interface PostShowPageProps extends PageProps {
  post: Post;
}

export interface PostEditPageProps extends PageProps {
  post: Post;
}

export interface PostCreatePageProps extends PageProps {}

export interface ProfileEditPageProps extends PageProps {
  user: User;
  mustVerifyEmail: boolean;
  status?: string;
}

export interface LoginPageProps extends PageProps {
  status?: string;
}

export interface RegisterPageProps extends PageProps {}

export interface ForgotPasswordPageProps extends PageProps {
  status?: string;
}

export interface ResetPasswordPageProps extends PageProps {
  token: string;
  email: string;
}
