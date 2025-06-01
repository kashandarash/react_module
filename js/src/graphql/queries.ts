// Define the GraphQL query
import {gql} from "graphql-request";
import {Todo} from "../components/Item";

// Create constant for the query.
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

// Object that we expect in response above.
export type TodoItemsResponse = {
    TodoItems: {
        items: Todo[];
        total: number;
    };
};