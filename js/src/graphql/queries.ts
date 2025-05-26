// Define the GraphQL query
import {gql} from "graphql-request";
import {Todo} from "../components/Item";

export const GET_TODO_ITEMS = gql`
    query TodoItems {
        TodoItems {
            items {
                id
                title
                completed
            }
            total
        }
    }
`;

export type TodoItemsResponse = {
    TodoItems: {
        items: Todo[];
        total: number;
    };
};