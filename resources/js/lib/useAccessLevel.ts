export const accessLevels: Record<number, string> = {
    1: 'Administrator',
    2: 'Human Resources for Health',
    3: 'PDOHO Focal',
    4: 'Development Management Officer',
};

export const getAccessLevelLabel = (level: number): string => {
    return accessLevels[level] ?? 'Unknown';
};