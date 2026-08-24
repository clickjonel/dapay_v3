import type { Component } from 'vue';
import { NotebookText, ClipboardList } from '@lucide/vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

export interface NavChild {
    name: string;
    route: string;
}

export interface NavGroup {
    name: string;
    icon: Component;
    children: NavChild[];
}

export const isNavigating = ref(false);

export const navigationGroups: NavGroup[] = [
    {
        name: 'Workbook',
        icon: NotebookText,
        children: [
            { name: 'Primary Health Care Indicators', route: '#' },
            { name: 'Barangay Indicators', route: '#' },
        ],
    },
    {
        name: 'Barangays',
        icon: ClipboardList,
        children: [
            { name: 'Barangays', route: '/barangays' },
        ],
    },
    {
        name: 'Indicators',
        icon: ClipboardList,
        children: [
            { name: 'Organizational', route: '/org-indicators' },
            { name: 'Programmatic', route: '/prog-indicators' },
            { name: 'Disaggregations', route: '/disaggregations' },
        ],
    },
    {
        name: 'Programs',
        icon: ClipboardList,
        children: [
            { name: 'Programs', route: '/programs' },
        ],
    },
    {
        name: 'Users',
        icon: ClipboardList,
        children: [
            { name: 'Users', route: '/users' },
        ],
    },
    {
        name: 'Teams',
        icon: ClipboardList,
        children: [
            { name: 'Teams', route: '/teams' },
        ],
    },
    {
        name: 'Reports',
        icon: ClipboardList,
        children: [
            { name: 'Reports', route: '/reports' },
        ],
    },
    {
        name: 'PK Activities',
        icon: ClipboardList,
        children: [
            { name: 'PK Activities', route: '/pk-activities' },
        ],
    },
];

export const navigate = (url: string) => {
    router.visit(url, {
        onStart: () => {
            isNavigating.value = true;
        },
        onFinish: () => {
            isNavigating.value = false;
        },
    });
};