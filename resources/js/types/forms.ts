export interface CreatePostFormData {
  title: string;
  content: string;
}

export interface UpdatePostFormData {
  title: string;
  content: string;
}

export interface CreateCommentFormData {
  comment: string;
}

export interface UpdateProfileFormData {
  name: string;
  email: string;
}

export interface UpdatePasswordFormData {
  current_password: string;
  password: string;
  password_confirmation: string;
}

export interface DeleteAccountFormData {
  password: string;
}

// Auth form types
export interface LoginFormData {
  email: string;
  password: string;
  remember: boolean;
}

export interface RegisterFormData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
  terms: boolean;
}

export interface ForgotPasswordFormData {
  email: string;
}
