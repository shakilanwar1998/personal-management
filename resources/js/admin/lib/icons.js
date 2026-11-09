import {
    Baby, Briefcase, Bus, Car, CircleDollarSign, CircleDot, CircleHelp, Clapperboard, Coffee, Dumbbell, FileText,
    Fuel, Gift, GraduationCap, HandHeart, HeartPulse, Home, Landmark, Laptop, Package, PartyPopper, PiggyBank, Plane,
    Receipt, Send, ShieldCheck, Shirt, ShoppingBag, ShoppingCart, Smartphone, Smile, Sparkles, Sprout, Store,
    TrendingDown, TrendingUp, User, Users, Utensils, Wallet, Wifi, Wrench, Zap, Cat, Book, Music, Gamepad2, Stethoscope,
    Pill, Train, Bike, Hammer, Tv, Target, Trophy, Umbrella, Building2, Banknote,
} from 'lucide-vue-next';

/** Icons that can be picked for categories and goals (stored by name). */
export const ICONS = {
    'baby': Baby, 'banknote': Banknote, 'bike': Bike, 'book': Book, 'briefcase': Briefcase, 'building': Building2,
    'bus': Bus, 'car': Car, 'cat': Cat, 'circle-dollar-sign': CircleDollarSign, 'circle-dot': CircleDot,
    'circle-help': CircleHelp, 'clapperboard': Clapperboard, 'coffee': Coffee, 'dumbbell': Dumbbell,
    'file-text': FileText, 'fuel': Fuel, 'gamepad': Gamepad2, 'gift': Gift, 'graduation-cap': GraduationCap,
    'hammer': Hammer, 'hand-heart': HandHeart, 'heart-pulse': HeartPulse, 'home': Home, 'landmark': Landmark,
    'laptop': Laptop, 'music': Music, 'package': Package, 'party-popper': PartyPopper, 'piggy-bank': PiggyBank,
    'pill': Pill, 'plane': Plane, 'receipt': Receipt, 'send': Send, 'shield-check': ShieldCheck, 'shirt': Shirt,
    'shopping-bag': ShoppingBag, 'shopping-cart': ShoppingCart, 'smartphone': Smartphone, 'smile': Smile,
    'sparkles': Sparkles, 'sprout': Sprout, 'stethoscope': Stethoscope, 'store': Store, 'target': Target,
    'train': Train, 'trending-down': TrendingDown, 'trending-up': TrendingUp, 'trophy': Trophy, 'tv': Tv,
    'umbrella': Umbrella, 'user': User, 'users': Users, 'utensils': Utensils, 'wallet': Wallet, 'wifi': Wifi,
    'wrench': Wrench, 'zap': Zap,
};

export const ICON_NAMES = Object.keys(ICONS);

export function iconFor(name) {
    return ICONS[name] || CircleDot;
}
