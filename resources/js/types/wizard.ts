export type LogicOperator =
    | 'exists' // implemented
    | '==='
    | '!=='
    | '>'
    | '<'
    | '>='
    | '<='
    | 'in'
    | 'not in'
    | 'length'
    | 'regex'
    | 'starts with'
    | 'ends with'
    | 'contains';

export interface LogicRule {
    target: WizardField['name'];
    operator: LogicOperator;

    value?: unknown; // pass a static value
    keyOfValue?: WizardField['name']; // or look up a value from another field
}

export interface Wizard {
    // metadata
    id: string;
    name: string;
    description?: string;

    // form handling
    endpoint: string;
    method: string;

    // rendering
    title?: string;
    intro?: string;
    steps: WizardStep[];
    rules?: LogicRule[];
}

export interface WizardStep {
    // metadata
    id: string;
    name: string;
    description?: string;

    // rendering
    title?: string;
    intro?: string;
    sections: WizardSection[];
    rules?: LogicRule[];
}

export interface WizardSection {
    // metadata
    id: string;
    name: string;
    description?: string;

    // rendering
    title?: string;
    intro?: string;
    columns: WizardField[][];
    rules?: LogicRule[];
}

export interface WizardField {
    // metadata
    id: string;
    name: string;
    description?: string;

    // form handling
    slug: string;
    required?: boolean;

    // rendering
    title?: string;
    intro?: string;
    component: string;
    props?: WizardFieldProps;
    rules?: LogicRule[];
}

export interface WizardFieldProps {
    [key: string]: unknown;
    required?: boolean;
    label?: string;
    detailsText?: string;
    asyncOptions?: WizardAsyncOptions;
    error?: string;
    viewOnly?: boolean;
}

export interface WizardAsyncOptions {
    route: string;
    routeParams?: Record<string, string>;
    routeParamsQuery?: Record<string, string>;
    method: string;
    query: Record<string, string>;
    transform?: (data: unknown) => Record<string, unknown>;
}
