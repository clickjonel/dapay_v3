import type { Component } from 'vue';
import { NotebookText, ClipboardList } from '@lucide/vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

export interface NavChild {
    name: string;
    route: string;
    accessLevels?: number[]; // omit = visible to everyone the parent group allows
}

export interface NavGroup {
    name: string;
    icon: Component;
    accessLevels?: number[]; // omit = visible to all access levels
    children: NavChild[];
}

export const isNavigating = ref(false);

export const navigationGroups: NavGroup[] = [
    {
        name: 'Workbook',
        icon: NotebookText,
        accessLevels: [1, 3, 4],
        children: [
            { name: 'Primary Health Care Indicators', route: '#' },
            { name: 'Barangay Indicators', route: '#' },
            { name: '4Ps Report', route: '/workbook/4ps' },
            { name: 'Total Clients Served', route: '/workbook/total-clients-served' },
        ],
    },
    {
        name: 'Barangays',
        icon: ClipboardList,
        accessLevels: [1, 2, 3, 4],
        children: [
            { name: 'Barangays', route: '/barangays' },
        ],
    },
    {
        name: 'Indicators',
        icon: ClipboardList,
        accessLevels: [1],
        children: [
            { name: 'Organizational', route: '/org-indicators' },
            { name: 'Programmatic', route: '/prog-indicators' },
            { name: 'Disaggregations', route: '/disaggregations' },
        ],
    },
    {
        name: 'Programs',
        icon: ClipboardList,
        accessLevels: [1],
        children: [
            { name: 'Programs', route: '/programs' },
        ],
    },
    {
        name: 'Users',
        icon: ClipboardList,
        accessLevels: [1],
        children: [
            { name: 'Users', route: '/users' },
        ],
    },
    {
        name: 'Teams',
        icon: ClipboardList,
        accessLevels: [1, 2, 3, 4],
        children: [
            { name: 'Teams', route: '/teams' },
        ],
    },
    // {
    //     name: 'Reports',
    //     icon: ClipboardList,
    //     accessLevels: [1, 2, 3, 4],
    //     children: [
    //         { name: 'Reports', route: '/reports' },
    //     ],
    // },
    {
        name: 'PK Activities',
        icon: ClipboardList,
        accessLevels: [1, 2, 3, 4],
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