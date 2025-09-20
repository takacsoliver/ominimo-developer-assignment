export interface BaseEntity {
  id: number;
  created_at: string;
  updated_at: string;
}

export interface ApiResponse<T = any> {
  data?: T;
  message?: string;
  errors?: Record<string, string[]>;
}

export interface FlashMessage {
  success?: string;
  error?: string;
  warning?: string;
  info?: string;
}
