import type { BaseEntity } from './base';

export interface User extends BaseEntity {
  name: string;
  email: string;
  email_verified_at?: string;
  roles?: Role[];
  permissions?: Permission[];
}

export interface Role {
  id: number;
  name: string;
}

export interface Permission {
  id: number;
  name: string;
}

export interface Author {
  id: number;
  name: string;
  email: string;
}
