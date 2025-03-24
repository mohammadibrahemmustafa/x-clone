import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
}

export interface User {
    id: number;
    name: string;
    email: string;
    bio?: string;
    avatar_url?: string | File;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

interface TweetUser {
  id: string;
  username: string;
  name: string;
  avatar_url?: string | null;
}

export interface Tweet {
  id: string;
  content: string;
  created_at: string;
  user: TweetUser;
}

export interface FeedProps {
  tweets: {
    data: Tweet[];
    next_page_url: string | null;
  };
  maxTweetLength: number;
}
