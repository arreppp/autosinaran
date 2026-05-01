export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
    flash: {
        success?: string;
        error?: string;
    };
};

export interface Package {
    id: number;
    name: string;
    description: string | null;
    price: number;
    items: string[];
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

export type BookingStatus =
    | 'pending_payment'
    | 'payment_review'
    | 'confirmed'
    | 'cancelled'
    | 'completed';

export interface Booking {
    id: number;
    booking_number: string;
    customer_name: string;
    customer_email: string;
    customer_phone: string;
    vehicle_plate: string;
    vehicle_model: string;
    package_id: number;
    package?: Package;
    booking_date: string;
    status: BookingStatus;
    notes: string | null;
    payment?: Payment;
    created_at: string;
    updated_at: string;
}

export type PaymentMethod = 'manual_transfer' | 'billplz' | 'stripe';
export type PaymentStatus = 'pending' | 'paid' | 'failed' | 'refunded';

export interface Payment {
    id: number;
    booking_id: number;
    amount: number;
    method: PaymentMethod;
    status: PaymentStatus;
    payment_proof_path?: string;
    transaction_ref?: string;
    paid_at?: string;
    created_at: string;
    updated_at: string;
}

export interface BlockedDate {
    id: number;
    date: string;
    reason: string | null;
    created_at: string;
    updated_at: string;
}

export type DateAvailability = 'available' | 'full' | 'blocked' | 'past';

export type AvailabilityMap = Record<string, DateAvailability>;

export interface PaginatedData<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
    links: { url: string | null; label: string; active: boolean }[];
}
